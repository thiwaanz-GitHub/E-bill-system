public class Ebill
{
    private int previousM;
    private int presentM;
    private int units;
	private int days;
    private float cost;
    
    public Ebill(int pastM, int presentM, int days)
    {
        this.previousM = pastM;
        this.presentM = presentM;
		this.days = days;
    }
	
	public Ebill(int u, int d)
	{
		this.units = u;
		this.days = d;
	}
    
    public void Units()
    {
        units = presentM - previousM;
		System.out.println("Total Units : " + units);
    }
	
	public void total()
	{
		while(units > 0)
		{
			if(units >= days)
			{
				cost = days * 7.85F;
				units = units - days;
				if(units >= days)
				{
					cost = cost + (days * 7.85F);
					units = units - days;
					if(units >= days)
					{
						cost = cost + (days*10);
						units = units - days;
						if(units >= days)
						{
							cost = cost + (days*27.75F);
							units = units - days;
							
							//To All Other Units
							cost = cost + (units*32);
							break;
						}
						else
						{
							cost = cost + (units*27.75F);
							break;
						}
					}
					else
					{
						cost = cost + (units*10);
						break;
					}
				}
				else
				{
					cost = cost + (units*7.85F);
					break;
				}
			}
			else
			{
				cost = units * 7.85F;
				break;
			}
		}
		System.out.println("Total cost : " + cost);
	}
}
