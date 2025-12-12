package main

import (
	"bufio"
	"fmt"
	"log"
	"math"
	"os"
	"sort"
	"strconv"
	"strings"
)

type position struct {
	x, y, z int
}

type closePositions struct {
	a    position
	b    position
	dist int
}

type circuit struct {
	positions []position
}

func (c *circuit) contains(p position) bool {
	for _, pos := range c.positions {
		if pos == p {
			return true
		}
	}

	return false
}

func (c *circuit) add(p position) {
	if !c.contains(p) {
		c.positions = append(c.positions, p)
	}
}

func (c *circuit) len() int {
	return len(c.positions)
}

func (c *circuit) addWithOwner(p position, owner map[string]int, idx int) {
	k := key(p)
	if _, ok := owner[k]; ok {
		return
	}
	c.positions = append(c.positions, p)
	owner[k] = idx
}

func main() {
	positions, err := parseInput("input.txt")
	if err != nil {
		log.Fatal(err)
	}

	fmt.Println(part1(positions))
	fmt.Println(part2(positions))
}

func part1(positions []position) int {
	var distances []closePositions

	for i := 0; i < len(positions); i++ {
		for j := i + 1; j < len(positions); j++ {
			a := positions[i]
			b := positions[j]
			distances = append(distances, closePositions{
				a:    a,
				b:    b,
				dist: distance(a, b),
			})
		}
	}

	sort.Slice(distances, func(a, b int) bool {
		return distances[a].dist < distances[b].dist
	})

	circuits := make([]circuit, 0)
	owner := make(map[string]int)

	for i := 0; i < 1000; i++ {
		a := distances[i].a
		b := distances[i].b

		ia, oka := owner[key(a)]
		ib, okb := owner[key(b)]

		switch {
		case !oka && !okb:
			circuits = append(circuits, circuit{})
			idx := len(circuits) - 1
			circuits[idx].addWithOwner(a, owner, idx)
			circuits[idx].addWithOwner(b, owner, idx)

		case oka && !okb:
			circuits[ia].addWithOwner(b, owner, ia)

		case !oka:
			circuits[ib].addWithOwner(a, owner, ib)

		default:
			if ia != ib {
				keep, kill := ia, ib
				if len(circuits[kill].positions) > len(circuits[keep].positions) {
					keep, kill = kill, keep
				}
				mergeCircuits(&circuits, owner, keep, kill)
			}
			ic := owner[key(a)]
			circuits[ic].addWithOwner(a, owner, ic)
			circuits[ic].addWithOwner(b, owner, ic)
		}
	}

	sort.Slice(circuits, func(a, b int) bool {
		return circuits[a].len() > circuits[b].len()
	})

	return circuits[0].len() * circuits[1].len() * circuits[2].len()
}

func part2(positions []position) int {
	var distances []closePositions

	for i := 0; i < len(positions); i++ {
		for j := i + 1; j < len(positions); j++ {
			a := positions[i]
			b := positions[j]
			distances = append(distances, closePositions{
				a:    a,
				b:    b,
				dist: distance(a, b),
			})
		}
	}

	sort.Slice(distances, func(a, b int) bool {
		return distances[a].dist < distances[b].dist
	})

	circuits := make([]circuit, 0)
	owner := make(map[string]int)

	for i := 0; i < len(distances); i++ {
		a := distances[i].a
		b := distances[i].b

		ia, oka := owner[key(a)]
		ib, okb := owner[key(b)]

		switch {
		case !oka && !okb:
			circuits = append(circuits, circuit{})
			idx := len(circuits) - 1
			circuits[idx].addWithOwner(a, owner, idx)
			circuits[idx].addWithOwner(b, owner, idx)

		case oka && !okb:
			circuits[ia].addWithOwner(b, owner, ia)

		case !oka:
			circuits[ib].addWithOwner(a, owner, ib)

		default:
			if ia != ib {
				keep, kill := ia, ib
				if len(circuits[kill].positions) > len(circuits[keep].positions) {
					keep, kill = kill, keep
				}
				mergeCircuits(&circuits, owner, keep, kill)
			}
			ic := owner[key(a)]
			circuits[ic].addWithOwner(a, owner, ic)
			circuits[ic].addWithOwner(b, owner, ic)
		}

		if circuits[0].len() == len(positions) {
			return int(a.x * b.x)
		}
	}

	return 0
}

func mergeCircuits(circuits *[]circuit, owner map[string]int, keep, kill int) {
	for _, p := range (*circuits)[kill].positions {
		(*circuits)[keep].positions = append((*circuits)[keep].positions, p)
		owner[key(p)] = keep
	}

	last := len(*circuits) - 1
	(*circuits)[kill] = (*circuits)[last]
	*circuits = (*circuits)[:last]

	if kill != last {
		for _, p := range (*circuits)[kill].positions {
			owner[key(p)] = kill
		}
	}
}

func parseInput(input string) ([]position, error) {
	file, err := os.Open(input)
	if err != nil {
		return nil, fmt.Errorf("could not open %s: %v", input, err)
	}
	defer file.Close()

	var positions []position
	scanner := bufio.NewScanner(file)
	for scanner.Scan() {
		line := scanner.Text()
		parts := strings.Split(line, ",")

		positions = append(positions, position{
			x: stringToInt(parts[0]),
			y: stringToInt(parts[1]),
			z: stringToInt(parts[2]),
		})
	}

	return positions, nil
}

func stringToInt(s string) int {
	i, _ := strconv.Atoi(s)

	return i
}

func distance(a, b position) int {
	dx := a.x - b.x
	dy := a.y - b.y
	dz := a.z - b.z

	return int(math.Sqrt(float64(dx*dx + dy*dy + dz*dz)))
}

func key(p position) string {
	return fmt.Sprintf("%g,%g,%g", p.x, p.y, p.z)
}
